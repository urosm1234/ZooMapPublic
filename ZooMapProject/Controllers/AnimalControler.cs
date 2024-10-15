using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using ZooMapProject.Models;
using ZooMapProject.Contracts;

namespace ZooMapProject.Controllers
{

    [Route("api/Coordinates/")]
    [ApiController]
    public class AnimalControler : Controller
    {
        // GET: AnimalControler
        [HttpPut("{id?}/{coordinatesH?}/{coordinatesW?}")]
        public async Task<IActionResult> UpdateCoordinates(int id, float coordinatesH, float coordinatesW, AnimalsPostModel model)
        {
            var url = "https://dikmgcsvbdhiixabjhrg.supabase.co";
            var key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRpa21nY3N2YmRoaWl4YWJqaHJnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjgzOTA1NDYsImV4cCI6MjA0Mzk2NjU0Nn0.XUhTozS2LrVs1AnJ-lWPLiDuYF4YES0l8P4mXr9n9wA";

            var options = new Supabase.SupabaseOptions
            {
                AutoConnectRealtime = true
            };
            Console.WriteLine(model.id);
            Console.WriteLine(model.coordinatesH);
            var superbase = new Supabase.Client(url, key, options);

            var update = await superbase.From<AnimalModel>()
            .Where(x => x.id == model.id)
            .Single();
            update.coordinatesH = model.coordinatesH;
            update.coordinatesW = model.coordinatesW;
            var response = await update.Update<AnimalModel>();
            Console.WriteLine(response);
            if(response!=null)
            {
                return Ok();
            }
            else
            {
                return NotFound();
            }

        }


    }
}
