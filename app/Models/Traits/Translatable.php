<?php
	
	
	namespace App\Models\Traits;
	
	
	use App\Models\Translation;
	use Illuminate\Database\Eloquent\Relations\MorphMany;
	
	trait Translatable
	{
		
		private $languages = ['ar', 'en', 'fr'];
		
		public function getTranslate($key = null, $lang = 'en')
		{
			$query = $this->translations();
			$query = $this->addKey($query, $key);
			$query = $this->addLang($query, $lang);
			$raw = $query->first();
			return $raw ? $raw->content : "";
		}
		
		public function translations()
		{
			return $this->morphMany(Translation::class, 'translatable');
		}
		
		private function addKey(MorphMany $query, $key)
		{
			if($key)
				return $query->where('key', $key);
			
			return $query;
		}
		
		private function addLang(MorphMany $query, $lang)
		{
			$lang = $this->getLanguage($lang);
			return $query->where('language', $lang);
		}
		
		private function getLanguage($lang)
		{
			if(in_array($lang, $this->languages))
				return $lang;
			return 'en';
		}
		
		public function addTranslate($content, $key = null, $lang = 'en')
		{
			
			if(is_array($content)) {
				$language = [];
				foreach($content as $langKey => $value) {
					$language[] = $this->storeTranslation($value, $key, $langKey);
				}
				return $language;
			} else {
				return $this->storeTranslation($content, $key , $lang);
			}
			
		}
		
		
		private function storeTranslation($content, $key , $lang = 'en')
		{
			return $this->translations()->create(
				[
					'language' => $this->getLanguage($lang),
					'key' => $key,
					'content' => $content,
				]
			);
		}
		
	}