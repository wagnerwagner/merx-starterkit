<?php
/**
 * Provides the template with the fields of the  blueprint section “personalData”
 * (/site/blueprints/sections/personal-data.yml) of the order blueprint
 * (/site/blueprints/pages/order.yml).
 *
 * More information about blueprints in the frontend:
 * https://getkirby.com/docs/cookbook/templating/blueprints-in-frontend
 */

return function (\Kirby\Cms\Site $site) {
    $orderBlueprint = Kirby\Cms\Blueprint::factory('pages/order', null, $site);
    $orderBlueprintSection = $orderBlueprint->section('personalData');

    return [
        'fields' => $orderBlueprintSection ? $orderBlueprintSection->toArray()['fields'] : [],
        'message' => merx()->getMessage(),
    ];
};
