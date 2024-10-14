using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using ZooMapProject.Models;
using System.Web;
using ZooMapProject.Contracts;
using Newtonsoft.Json;



namespace ZooMapProject.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;

    public HomeController(ILogger<HomeController> logger)
    {
        _logger = logger;
    }

    public async Task<IActionResult> Index()
    {
        var url = "https://dikmgcsvbdhiixabjhrg.supabase.co";
        var key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRpa21nY3N2YmRoaWl4YWJqaHJnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjgzOTA1NDYsImV4cCI6MjA0Mzk2NjU0Nn0.XUhTozS2LrVs1AnJ-lWPLiDuYF4YES0l8P4mXr9n9wA";


        var options = new Supabase.SupabaseOptions
            {
                AutoConnectRealtime = true
            };

        var supabase = new Supabase.Client(url, key, options);
        //await supabase.InitializeAsync();

        var result1 = await supabase.From<AnimalInfoModel>()
        .Get();
        var animals = result1.Models;
        if(animals == null)
        {
            return NotFound();
        }
        AnimalsGetResponse [] responses = new AnimalsGetResponse[animals.Count];
        for(int i=0;i<animals.Count;i++)
        {
            responses[i] = new AnimalsGetResponse{
                id = i,
                name = animals[i].AnimalModel[0].name,
                title = animals[i].title,
                coordinatesH = animals[i].AnimalModel[0].coordinatesH,
                coordinatesW = animals[i].AnimalModel[0].coordinatesW,
                desc1 = animals[i].desc1,
                desc2 = animals[i].desc2,
                desc3 = animals[i].desc3,
                paragraph = animals[i].paragraph
            };
        }
        ViewBag.AnimalDataJson = JsonConvert.SerializeObject(responses);
        return View();
    }

    public IActionResult Privacy()
    {
        return View();
    }

    public IActionResult Map(){
        return View();
    }



    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }
}


