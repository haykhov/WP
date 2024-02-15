<?php

namespace netzlodern\pwr\actions;

use netzlodern\pwr\models\Country;
use netzlodern\pwr\models\Region;
use netzlodern\pwr\models\RegionGroup;
use netzlodern\pwr\models\City;
use netzlodern\pwr\models\Accommodation;

class AjaxHandler
{
    public function __construct()
    {
        add_action('wp_ajax_pwr_search_destination', [$this, 'pwrSearchDestination']);
        add_action('wp_ajax_nopriv_pwr_search_destination', [$this, 'pwrSearchDestination']);
    }

    public function pwrSearchDestination(): void
    {
        $responseHtml = '';

        $term = isset($_POST['term']) ? trim(sanitize_text_field($_POST['term'])) : '';

        if (!empty($term)) {
            $results['countries'] = Country::search($term);
            $results['regionGroups'] = RegionGroup::search($term);
            $results['regions'] = Region::search($term);
            $results['cities'] = City::search($term);
            $results['accommodations'] = Accommodation::search($term);

            // Create the response HTML
            $responseHtml = '<div>';
            foreach ($results as $section => $items) {
                if (empty($items)) {
                    continue;
                }

                $responseHtml .= '<div class="pwr-autocomplete-section">';
                $responseHtml .= '<div class="pwr-autocomplete-section-title">' . ucfirst($this->getSectionName($section)) . '</div>';
                foreach ($items as $item) {
                    // Highlight the search term in the name
                    $highlightedName = $this->pwrHighlightTerm($item->name, $term);

                    $countryIdSection = (isset($item->country_id)) ? '<input type="hidden" class="pwr-country-id-section" value="' . $item->country_id . '">' : '';
                    $regionGroupIdSection = (isset($item->region_group_id)) ? '<input type="hidden" class="pwr-region-group-id-section" value="' . $item->region_group_id . '">' : '';
                    $regionIdSection = (isset($item->region_id)) ? '<input type="hidden" class="pwr-region-id-section" value="' . $item->region_id . '">' : '';
                    $cityIdSection = (isset($item->city_id)) ? '<input type="hidden" class="pwr-city-id-section" value="' . $item->city_id . '">' : '';
                    $accommodationIdSection = (isset($item->accommodation_id)) ? '<input type="hidden" class="pwr-accommodation-id-section" value="' . $item->accommodation_id . '">' : '';

                    $responseHtml .=
                        '<div class="pwr-autocomplete-item">' .
                        $highlightedName . $countryIdSection . $regionGroupIdSection . $regionIdSection . $cityIdSection . $accommodationIdSection .
                        '</div>';
                }
                $responseHtml .= '</div>';
            }
            $responseHtml .= '</div>';
        }

        wp_send_json_success($responseHtml);
    }

    private function pwrHighlightTerm(string $text, string $term): string
    {
        $term = preg_quote($term, '/');
        return preg_replace("/($term)/i", '<span class="pwr-highlight">$1</span>', $text);
    }

    private function getSectionName(string $name): string
    {
        $nameMapping = [
            'countries' => 'Länder',
            'regionGroups' => 'Regionsgruppen',
            'regions' => 'Regionen',
            'cities' => 'Städte',
            'accommodations' => 'Hotels',
        ];

        return $nameMapping[$name] ?? '';
    }
}
