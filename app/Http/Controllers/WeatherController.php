<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Khill\Lavacharts\Lavacharts;

/**
 * Main Weather Controller.
 * 
 * @author mark
 *
 */
class WeatherController extends Controller
{
	/**
	 * doReport() that is the back end Form Post Handler for the Report Generator, as shown in the figure below, which performs the following functions:
	 * 
	 *  <br>
	 *  <img src="../images/app.jpg"/>
	 *  <br>
	 *  <ul>
	 *  <li>Gets the POSTED form parameters</li>
	 *  	- report = 0 for Lavachart Chart Report or 1 for Plotly Chart Report or 2 for Tabular HTML Data Report or 3 for Tabular jQuery Data Table Report<br/>
	 *  	- fromDate = report start date<br/>
	 *  	- toDate = report end date
	 *  	- device = device ID
	 *  <li>Validates the form data</li>
	 *  	- if validation fails the Weather View is redisplayed
	 *  <li>Calls the Web API to get the Weather Data using the GuzzleHttp Client</li>
	 *  	- GET request to the CloudService REST /get API passing a Device ID and From Date and To Date as API parameters<br/>
	 *  	- CloudService REST API returns JSON (see the ResponseDataModel and WeatherDataModel from the Cloud Services SDK JavaDocs)
	 *  <li>Renders a Weather Report</li>
	 *  	- If report is 0 then use Lavaharts to render a Line Graph forwarding to the WeatherReportChart View<br/>
	 *  	- Else if report is 1 then use Plotly to render a Line Graph forwarding to the WeatherReportChartPlotly View<br/>
	 *  	- Else if report is 2 then render an HTML Table forwarding to the WeatherReportTable<br/>
	 *  	- Else report then render an jQuery Data Table forwarding to the WeatherReportTablejQuery View
	 *  </ul>
	 *  
	 * See route setup in routes/web.php: HTTP POST to /doreport.
	 * 
	 * @param Request $request
	 * @return Weather View if validation error, WeatherReportChart View to display chart report, or WeatherReportTable View to display tabular report
	 *  
	 */
	public function doReport(Request $request)
	{
		// Get Report Type and its parameters
		$report = $request->input("report");
		$from = $request->input("fromDate");
		$to = $request->input("toDate");
		$device = $request->input("deviceId");
		
		// Validate the request parameters (note will automatically redirect back to Weather View if errors)
		$this->validateForm($request);
		
		// Call Web API to get Weather Data
        if (App::environment('azure'))
			$serviceURL = "https://markwsserve2.azurewebsites.net/cloudservices/rest/weather/";
        else if (App::environment('openshift'))
			$serviceURL = "http://test-marktest.7e14.starter-us-west-2.openshiftapps.com/cloudservices/rest/weather/";
		else if (App::environment('google'))
			$serviceURL = "https://cloud-workshop-services.appspot.com/rest/weather/";
		else if (App::environment('heroku'))
	        $serviceURL = "https://mark-servicesapp.herokuapp.com/rest/weather/";
	    else if (App::environment('amazon'))
	       $serviceURL = "http://services-app.us-east-2.elasticbeanstalk.com/rest/weather/";
	    else
			$serviceURL = "http://localhost:8080/cloudservices/rest/weather/";
		$api = "get";
		$uri = $api . "/" . $device . "/" . $from . "/" . $to;
		$username = "CloudWorkshop";
		$password = "dGVzdHRlc3Q=";
		$client = new Client(['base_uri' => $serviceURL]);
		$response = $client->request('GET', $uri, ['auth' => [$username, $password]]);
		
		// Process API Response
		if($response->getStatusCode() == 200)
		{
			// Convert JSON to Objects and render report
			$jsonObj = json_decode($response->getBody(), true);
			if($report == 0)
			{
				// If data then display Lava Chart else display error
				if(count($jsonObj['data']) != 0)
				{	
					// Build a Lavachart Datatable, Line Chart, and pass the Chart to the View to render
					$lava = new Lavacharts;
					$weatherData = $lava->DataTable();
					$weatherData->addStringColumn('Date')->addNumberColumn('Temp')->addNumberColumn('Pressure')->addNumberColumn('Humidity');
					foreach ($jsonObj['data'] as $item)
					{
						$weatherData->addRow([$item['date'], $item['temperature'], $item['pressure'], $item['humidity']]);
					}
					$lava->LineChart('Temps', $weatherData, ['title' => 'Weather in Date Range']);
					return view("weatherReportChart")->with("lava", $lava);
				}
				else
				{
					// Pass null Chart to display "no records found"
					return view("weatherReportChart")->with("lava", null);
				}
			}
			else if($report == 1)
			{
			    // If data then display Plotly Chart else display error
			    if(count($jsonObj['data']) != 0)
			    {
			        // Build a X and Y Line Chart Data and pass the Data to the View to render
			        $index = 0;
			        $dates = array();
			        $temperature = array();
			        $pressure = array();
			        $humidity = array();
			        foreach ($jsonObj['data'] as $item)
			        {
			            $dates[$index] = $item['date'];
			            $temperature[$index] = $item['temperature'];
			            $pressure[$index] = $item['pressure'];
			            $humidity[$index] = $item['humidity'];
			            ++$index;
			        }
			        return view("weatherReportChartPlotly")->with("data", ['dates' => json_encode($dates), 'temperature' => json_encode($temperature), 'pressure' => json_encode($pressure), 'humidity' => json_encode($humidity), 'title' => json_encode('Weather in Date Range')]);
			    }
			    else
			    {
			        // Pass null JSON to display "no records found"
			        return view("weatherReportChartPlotly")->with("json", null);
			    }
			}
			else if($report == 2)
			{
				// Pass the Weather data to the View to render in an HTML Table
				return view("weatherReportTable")->with("data", $jsonObj);
			}
			else
			{
			    // Pass the Weather data to the View to render in a jQuery Data Table
			    return view("weatherReportTablejQuery")->with("data", json_encode($jsonObj));
			}
		}
		else
		{
			return view("weatherError")->with("error", $response->getStatusCode());
		}
	}
	
	/**
	 * Private helper function to validate the Report Generation Form data.
	 * 
	 * @param Request $request
	 */
	private function validateForm(Request $request)
	{
		// Setup Data Validation Rules for Login Form
		$rules = ['fromDate' => 'Required | date_format:"Y-m-d H:i:s"',
				  'toDate' => 'Required | date_format:"Y-m-d H:i:s"'];
		
		// Run Data Validation Rules
		$this->validate($request, $rules);
	}
}
