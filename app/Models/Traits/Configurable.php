<?php


namespace App\Models\Traits;

use App\Models\Configuration;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

trait Configurable
{

	private $types = ['date', 'string', 'integer', 'float', 'boolean'];

	public function getConfig($key = null, $group = null, $buildInParse = true)
	{
		$configEntity = $this->getConfigEntity($key, $group);

		if ($configEntity)
			return $buildInParse ? $this->getParsedConfigValue($configEntity) : $configEntity->value;

		return null;
	}

	public function getConfigurations($group = null, $key = null)
	{
		$configurations = $this->configruations();
		$configurations = $this->addGroup($configurations, $group);
		$configurations = $this->addKey($configurations, $key);
		return  $configurations->get();
	}

	private function getParsedConfigValue(Configuration $configEntity)
	{
		$type = $this->getType($configEntity->type);

		if ($type) {
			if ($type == 'date')
				return $this->parseDateConfig($configEntity);


			if ($type == 'integer')
				return (int)$configEntity->value;

			if ($type == 'boolean')
				return (boolean)$configEntity->value;
		}


		return $configEntity->value;
	}


	public function parseDateConfig(Configuration $configEntity)
	{
		try {
			return Carbon::parse($configEntity->value);
		} catch (\Exception $e) {
			return  $configEntity->value;
		}
	}
	public function getConfigEntity($key = null, $group = null)
	{
		$query = $this->configruations();
		$query = $this->addGroup($query, $group);
		$query = $this->addKey($query, $key);
		return $query->orderByDesc('id')->first();
	}

	public function configruations()
	{
		return $this->morphMany(Configuration::class, 'configurable');
	}

	private function addKey(MorphMany $query, $key)
	{
		if ($key)
			return $query->where('key', strtoupper($key));

		return $query;
	}


	private function addGroup(MorphMany $query, $group)
	{
		if ($group)
			return $query->where('collection', strtoupper($group));

		return $query;
	}




	private function addType(MorphMany $query, $type)
	{
		$lang = $this->getType($type);
		return $query->where('type', $lang);
	}

	private function getType($type)
	{
		if (in_array($type, $this->types))
			return $type;

		return null;
	}

	public function addConfig($content, $key = null, $title = null, $type = null, $group = null)
	{

		$entity = $this->getConfigEntity($key, $group);

		if ($entity) {
			$entity->update([
				'title' => $title,
				'value' => $content,
				'collection' => strtoupper($group)
			]);
			return $entity;
		} else {
			return $this->configruations()->create(
				[
					'type' => $this->getType($type),
					'key' => strtoupper($key),
					'title' => $title,
					'value' => $content,
					'collection' => strtoupper($group)
				]
			);
		}
	}
}
