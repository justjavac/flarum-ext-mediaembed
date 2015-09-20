<?php namespace justjavac\Flarum\MediaEmbed;

use Flarum\Events\FormatterConfigurator;
use Flarum\Support\Extension as BaseExtension;
use Illuminate\Events\Dispatcher;
use s9e\TextFormatter\Configurator\Bundles\MediaPack;

class Extension extends BaseExtension
{
	public function listen(Dispatcher $events)
	{
		$events->subscribe(__NAMESPACE__ . '\\Listener');
	}
}

class Listener
{
	public function subscribe(Dispatcher $events)
	{
		$events->listen('Flarum\\Events\\FormatterConfigurator', [$this, 'addMediaSites']);
	}

	public function addMediaSites(FormatterConfigurator $event)
	{
		$event->configurator->templateChecker->remove('DisallowUnsafeDynamicCSS');
		$event->configurator->MediaEmbed->enableResponsiveEmbeds();

		// https://github.com/s9e/TextFormatter/blob/master/docs/Plugins/MediaEmbed/Add_custom.md
        // 网易云音乐
        $event->configurator->MediaEmbed->add(
            'music163',
            [
                'host'    => 'music.163.com',
                'extract' => "!music\\.163\\.com/#/song\\?id=(?'id'\\d+)!",
                'iframe'  => [
                    'width'  => 330,
                    'height' => 86,
                    'src'    => 'http://music.163.com/outchain/player?type=2&id={@id}&auto=1&height=66'
                ]
            ]
        );
        // 酷6
        $event->configurator->MediaEmbed->add(
            'ku6',
            [
                'host'    => 'ku6.com',
                'extract' => "!ku6\\.com/show/(?'id'[\\w\\.]+)\\.html!",
                'flash'  => [
                    'width'  => 480,
                    'height' => 400,
                    'flashvars' => "from=ku6",
                    'src'    => 'http://player.ku6.com/refer/{@id}/v.swf'
                ]
            ]
        );
		(new MediaPack)->configure($event->configurator);
	}
}

return __NAMESPACE__ . '\\Extension';