<?php

	namespace App\Providers;

	use Illuminate\Support\Facades\App;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;

	class TranslationServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap the application services.
		 *
		 * @return void
		 */
		public function boot()
		{
			$langFile = $this->phpTranslations('ar');
			View::share('app_transactions', $langFile);
		}

		private function phpTranslations($locale): array
        {
			$path = resource_path("lang/$locale");

			return collect(File::allFiles($path))->flatMap(
				function($file) use ($locale) {
					$key = ($translation = $file->getBasename('.php'));

					return [$key => trans($translation, [], $locale)];
				}
			)->toArray();
		}

//		private function jsonTranslations($locale)
//		{
//			$path = resource_path("lang/$locale.json");
//
//			if(is_string($path) && is_readable($path)) {
//				return json_decode(file_get_contents($path), true);
//			}
//
//			return [];
//		}
	}
