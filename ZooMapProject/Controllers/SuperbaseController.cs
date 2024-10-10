using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using ZooMapProject.Models;
using ZooMapProject.Contracts;
using System.Web;
using Microsoft.AspNetCore.Http.HttpResults;
using Newtonsoft.Json.Linq;
using System.Text.Json;
using System.Numerics;

namespace ZooMapProject.Controllers
{
    [Route("api/superbase")]
    [ApiController]

    public class SuperbaseController: Controller{

        [HttpGet("{id?}")]
        public async Task<IActionResult> Index()
        {
            var url = "https://dikmgcsvbdhiixabjhrg.supabase.co";
            var key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRpa21nY3N2YmRoaWl4YWJqaHJnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjgzOTA1NDYsImV4cCI6MjA0Mzk2NjU0Nn0.XUhTozS2LrVs1AnJ-lWPLiDuYF4YES0l8P4mXr9n9wA";


            var options = new Supabase.SupabaseOptions
                {
                    AutoConnectRealtime = true
                };

            var supabase = new Supabase.Client(url, key, options);
            await supabase.InitializeAsync();

            var result = await supabase.From<AnimalModel>()
            .Get();
            var animals = result.Models;
            if(animals == null)
            {
                return NotFound();
            }
            AnimalsGetResponse [] responses = new AnimalsGetResponse[animals.Count];
            for(int i=0;i<animals.Count;i++)
            {
                responses[i] = new AnimalsGetResponse{
                    name = animals[i].name,
                    imgUrl= animals[i].ImageUrl,
                    coordinatesH = animals[i].coordinatesH,
                    coordinatesW = animals[i].coordinatesW
                };
            }
            return Ok(responses);
        }
    }
}