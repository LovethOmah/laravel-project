<?php //

// namespace App\Http\Controllers; //

//use Illuminate\Http\Request; //

//class PrometheusController extends Controller//

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Prometheus\Storage\InMemory;

class PrometheusController extends Controller
{
    public function metrics()
    {
        // Use in-memory storage (metrics reset when the app restarts)
        $adapter = new InMemory();

        // Create a registry instance
        $registry = new CollectorRegistry($adapter);

        // Example metric: HTTP requests counter
        $counter = $registry->getOrRegisterCounter(
            'fms_app',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'status']
        );

        // Increment the counter (simulate a GET request with 200 status)
        $counter->inc(['GET', '200']);

        // Render metrics in Prometheus-compatible format
        $renderer = new RenderTextFormat();
        $metrics = $renderer->render($registry->getMetricFamilySamples());

        // Return metrics as plain text
        return response($metrics)
            ->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }
}
