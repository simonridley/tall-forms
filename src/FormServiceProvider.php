<?php

namespace Tanthammar\TallForms;

use BladeUIKit\Components\BladeComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Tanthammar\TallForms\Commands\MakeForm;

class FormServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([MakeForm::class]);
        }
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([__DIR__ . '/../config/tall-forms.php' => config_path('tall-forms.php')], 'form-config');
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/tall-forms')], 'form-views');

        \Livewire::component('tall-tags-update', \Tanthammar\TallForms\Tags\TagsFieldUpdate::class);
        \Livewire::component('tall-tags-create', \Tanthammar\TallForms\Tags\TagsFieldCreate::class);

        $this->bootViews();
        $this->prefixBladeUIComponents();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/tall-forms.php', 'tall-forms');
    }

    protected function bootViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'tall-forms');
        Blade::component('tall-forms::components.button', 'button');
        Blade::component('tall-forms::components.spinners.button', 'tall-spinner');
        Blade::component('tall-forms::components.input', 'tall-input');
        Blade::component('tall-forms::components.range', 'tall-range');
        Blade::component('tall-forms::components.checkbox', 'tall-checkbox');
        Blade::component('tall-forms::components.radio', 'tall-radio');
        Blade::component('tall-forms::components.select', 'tall-select');
        Blade::component('tall-forms::components.textarea', 'tall-textarea');
        Blade::component('tall-forms::components.label', 'tall-label');
        Blade::component('tall-forms::components.error-icon', 'tall-error-icon');
        Blade::component('tall-forms::components.field-wrapper', 'tall-field-wrapper');
        Blade::component('tall-forms::components.notification', 'tall-notification');
    }

    private function prefixBladeUIComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = 'tall-buk';
            /** @var BladeComponent $component */
            foreach (config('blade-ui-kit.components', []) as $alias => $component) {
                $blade->component($component, $alias, $prefix);
            }
        });
    }
}
