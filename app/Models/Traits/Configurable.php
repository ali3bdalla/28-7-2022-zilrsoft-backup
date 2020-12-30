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

	public function getConfig($key = null, $buildInParse = true)
	{
		$configEntity = $this->getConfigEntity($key);

		if ($configEntity)
			return $buildInParse ? $this->getParsedConfigValue($configEntity) : $configEntity->value;

		return null;
	}

	public function getConfigurations($group = null,$key = null)
	{
		$configurations = $this->configruations();
		$this->addGroup($configurations,$group);
		$this->addKey($configurations,$key);



		return  $configurations->get();
	}

	private function getParsedConfigValue(Configuration $configEntity)
	{
		$type = $this->getType($configEntity->type);

		if ($type) {
			if ($type == 'date')
				return $this->parseDateConfig($configEntity);
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
	public function getConfigEntity($key = null)
	{
		$query = $this->configruations();
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
			return $query->where('key', $key);

		return $query;
	}


	private function addGroup(MorphMany $query, $group)
	{
		if ($group)
			return $query->where('group', $group);

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

	public function addConfig($content, $key = null, $title = null,$type = null, $group = null)
	{

		return $this->configruations()->create(
			[
				'type' => $this->getType($type),
				'key' => $key,
				'title' => $title,
				'value' => $content,
				'group' => $group
			]
		);
	}
}
