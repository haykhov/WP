<?php

namespace netzlodern\pwr\shortcodes;

class TravelIbe
{
    private string $iframeUrl = 'https://dtps-ibe.o-rsb.de';
    private string $engine = '';
    private string $urlParams = '';
    private array $forbiddenKeys = [
        'doing_wp_cron',
        'taid',
        'css'
    ];

    public function __construct()
    {
        add_action('init', [$this, 'initializeTravelIbe']);
        add_shortcode('travel_ibe', [$this, 'renderShortcode']);
        add_action('wp_footer', [$this, 'enqueueAssets']);
    }

    public function initializeTravelIbe():void
    {
        $this->getTravelIbeParams();
        $this->getIframeUrl();
    }

    public function renderShortcode(): string
    {
        ob_start();
        include plugin_dir_path(__FILE__) . 'TravelIbeOutput.php';
        return ob_get_clean();
    }

    private function getTravelIbeParams(): void
    {
        foreach ($_GET as $key => $value) {
            if ($key == 'ibe') {
                $this->engine = $value;
                continue;
            }

            if (!in_array($key, $this->forbiddenKeys)) {
                $this->urlParams .= ($this->urlParams !== '' ? '&' : '') . "{$key}={$value}";
            }
        }
    }

    private function getIframeUrl(): void
    {
        $iframeUrlParams = $this->getIframeUrlParams();

        if ($this->urlParams !== '') {
            $iframeUrlParams .= "&$this->urlParams";

            if ($this->engine === '' || $this->engine === 'hotel') {
                $this->iframeUrl .= "/hotel{$iframeUrlParams}";
            } elseif ($this->engine === 'flight') {
                $this->iframeUrl .= "/offer{$iframeUrlParams}";
            }
        } else {
            $this->iframeUrl .= "/search{$iframeUrlParams}";
        }
    }

    private function getIframeUrlParams(): string
    {
        $taId = get_option('offer_ta_id');
        $iframeUrlParams = "?taid=$taId";
        $iframeUrlParams .= ($this->engine !== '') ? '&ibe=' . $this->engine : '';
        return $iframeUrlParams;
    }

    public function enqueueAssets(): void
    {
        wp_enqueue_script('travel-ibe-js');
    }
}

new TravelIbe();
