# jsdl-loader
A stand-alone JSON Service Description loader for Guzzle 5.x based on the ServiceDescriptionLoader class from Guzzle 3.x

## Usage

Based on the [guzzle/guzzle-services example](https://github.com/guzzle/guzzle-services/blob/0.5.0/README.rst).

```
use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use Webbj74\JSDL\Loader\ServiceDescriptionLoader;

$client = new Client();
$jsdlLoader = new ServiceDescriptionLoader();
$description = new Description($jsdlLoader->load($pathToServiceDescription));

// Create a new Guzzle Service Description client
$guzzleClient = new GuzzleClient($client, $description);
```
