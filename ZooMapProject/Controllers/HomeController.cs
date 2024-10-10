using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using ZooMapProject.Models;
using System.Web;

namespace ZooMapProject.Controllers;

public class HomeController : Controller
{
    private readonly ILogger<HomeController> _logger;

    public HomeController(ILogger<HomeController> logger)
    {
        _logger = logger;
    }

    public IActionResult Index(string animal)
    {
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


