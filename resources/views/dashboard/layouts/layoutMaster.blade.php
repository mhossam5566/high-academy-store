@isset($pageConfigs)
    {!! App\Helpers\Helpers::updatePageConfig($pageConfigs) !!}
@endisset
@php
    $configData = App\Helpers\Helpers::appClasses();
@endphp

@isset($configData['layout'])
    @include(
        $configData['layout'] === 'horizontal'
            ? 'dashboard.layouts.horizontalLayout'
            : ($configData['layout'] === 'blank'
                ? 'dashboard.layouts.blankLayout'
                : ($configData['layout'] === 'front'
                    ? 'dashboard.layouts.layoutFront'
                    : 'dashboard.layouts.contentNavbarLayout')))
@endisset
