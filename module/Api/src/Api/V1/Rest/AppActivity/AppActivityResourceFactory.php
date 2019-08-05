<?php
namespace Api\V1\Rest\AppActivity;

class AppActivityResourceFactory
{
    public function __invoke($services)
    {
		$mapper = $services->get('Api\V1\Rest\AppActivity\AppActivityMapper');
        return new AppActivityResource($mapper);
    }
}
