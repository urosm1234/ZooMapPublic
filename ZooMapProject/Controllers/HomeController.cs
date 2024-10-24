using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using ZooMapProject.Models;
using System.Web;
using ZooMapProject.Contracts;
using Newtonsoft.Json;
using Microsoft.VisualBasic;



namespace ZooMapProject.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;
    private readonly string url, key;
    private readonly Supabase.SupabaseOptions options;
    private readonly Supabase.Client client;

    public HomeController(ILogger<HomeController> logger)
    {
        _logger = logger;
        url = "https://dikmgcsvbdhiixabjhrg.supabase.co";
        key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRpa21nY3N2YmRoaWl4YWJqaHJnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjgzOTA1NDYsImV4cCI6MjA0Mzk2NjU0Nn0.XUhTozS2LrVs1AnJ-lWPLiDuYF4YES0l8P4mXr9n9wA";
        options = new Supabase.SupabaseOptions
            {
                AutoConnectRealtime = true
            };
        client = new Supabase.Client(url, key, options);
    }

    private async Task<List<AnimalsGetResponse>> GetCommand ()
    {
        var result1 = await client.From<AnimalInfoModel>()
        .Get();
        var animals = result1.Models;
        if(animals == null)
        {
            return null;
        }
        List<AnimalsGetResponse> responses = new List<AnimalsGetResponse>(animals.Count);
        for(int i=0;i<animals.Count;i++)
        {
            responses.Add(new AnimalsGetResponse{
                array_id = i,
                database_id = animals[i].AnimalModel[0].id,
                name = animals[i].AnimalModel[0].name,
                title = animals[i].title,
                coordinatesH = animals[i].AnimalModel[0].coordinatesH,
                coordinatesW = animals[i].AnimalModel[0].coordinatesW,
                desc1 = animals[i].desc1,
                desc2 = animals[i].desc2,
                desc3 = animals[i].desc3,
                paragraph = animals[i].paragraph
            });
        }
        return responses;
    }
    public async Task<IActionResult> Index()
    {
        List<AnimalsGetResponse> responses = await GetCommand();
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

    [Route("Home/admin/")]
    [HttpPost]
    public async Task<IActionResult> postAdmin()
    {
         await client.Auth.SignOut();
            
           
        if(Request.Form["gmail"] == "uki.mar@gmail.com" && Request.Form["password"] == "1234567890")
        {
                    await client.Auth.SignIn(Request.Form["gmail"], Request.Form["password"]);
                   
                    Console.WriteLine(client.Auth.CurrentUser.Email);
        }
        else
        {
             Response.Redirect("Login");
             
        }
        List<AnimalsGetResponse> responses = await GetCommand();
        ViewBag.AnimalDataJson = JsonConvert.SerializeObject(responses);
        return View("Admin");
    }
    [HttpGet]
    public async Task<IActionResult> Admin()
    {
        
        if(client.Auth.CurrentUser != null)
        {
            
        List<AnimalsGetResponse> responses = await GetCommand();
        ViewBag.AnimalDataJson = JsonConvert.SerializeObject(responses);
            return View();
        }
        else
        Response.Redirect("Login");

        return NotFound();
    }

    public IActionResult Login()
    {
        return View();
    }

    [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
    public IActionResult Error()
    {
        return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
    }



}


