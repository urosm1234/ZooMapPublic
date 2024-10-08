using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using ZooMapProject.Models;
using System.Web;
using Microsoft.AspNetCore.Http.HttpResults;

namespace ZooMapProject.Controllers
{
    [Route("api/superbase")]
    [ApiController]

    public class SuperbaseController: Controller{
        [HttpGet("{id?}")]

        public async Task<ActionResult> Index()
        {
            var url = "https://dikmgcsvbdhiixabjhrg.supabase.co";
            var key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRpa21nY3N2YmRoaWl4YWJqaHJnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjgzOTA1NDYsImV4cCI6MjA0Mzk2NjU0Nn0.XUhTozS2LrVs1AnJ-lWPLiDuYF4YES0l8P4mXr9n9wA";


            var options = new Supabase.SupabaseOptions
                {
                    AutoConnectRealtime = true
                };

            var supabase = new Supabase.Client(url, key, options);
            await supabase.InitializeAsync();

            var result = await supabase.From<AnimalModel>().Get();
            Console.WriteLine(result);
            var cities = result.Model;
            
            return Ok(cities.name);
        }
    }
}