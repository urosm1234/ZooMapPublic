using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using ZooMapProject.Models;
using System.Web;
using ZooMapProject.Contracts;
using Newtonsoft.Json;
using Microsoft.VisualBasic;
using Supabase.Gotrue;



namespace ZooMapProject.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;
    private readonly string url, key;
    private readonly Supabase.SupabaseOptions options;
    private readonly Supabase.Client client;
    
    private readonly string SessionKeyName = "gmail";
    private readonly string SessionKeyPassword = "password";
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
        Console.WriteLine(1);
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

    public async Task<IActionResult> Admin()
    {
        if(!string.IsNullOrEmpty(HttpContext.Session.GetString(SessionKeyName)))
        {
        
        var responses = await GetCommand();
        ViewBag.AnimalDataJson = JsonConvert.SerializeObject(responses);
        await client.Auth.SignIn(HttpContext.Session.GetString(SessionKeyName), HttpContext.Session.GetString(SessionKeyPassword));
        return View();
        }
        else
        return RedirectToAction("Login");
    }

    [Route("Home/try/")]
    [HttpPost]
    public async Task<IActionResult> Try()
    {
        await client.Auth.SignOut(); 
        if(Request.Form["gmail"] == "uki.mar@gmail.com" && Request.Form["password"] == "1234567890")
        {
            
            if (string.IsNullOrEmpty(HttpContext.Session.GetString(SessionKeyName)))
            {
                HttpContext.Session.SetString(SessionKeyName, Request.Form["gmail"]);
                HttpContext.Session.SetString(SessionKeyPassword,Request.Form["password"] );
            }
            return RedirectToAction("Admin");
        }
        else
        {
            return RedirectToAction("Login");
             
        }
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


