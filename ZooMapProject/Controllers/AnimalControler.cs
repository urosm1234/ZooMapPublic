using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;
using ZooMapProject.Models;
using ZooMapProject.Contracts;
using System.Data;

namespace ZooMapProject.Controllers
{

    [Route("api/Updates/")]
    [ApiController]
    public class AnimalControler : Controller
    {
        // GET: AnimalControler
        [HttpPut("{id?}")]
        public async Task<IActionResult> UpdateAnimal(int id, AnimalsPostModel model)
        {
            
            var url = "https://dikmgcsvbdhiixabjhrg.supabase.co";
            var key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImRpa21nY3N2YmRoaWl4YWJqaHJnIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjgzOTA1NDYsImV4cCI6MjA0Mzk2NjU0Nn0.XUhTozS2LrVs1AnJ-lWPLiDuYF4YES0l8P4mXr9n9wA";

            var options = new Supabase.SupabaseOptions
            {
                AutoConnectRealtime = true
            };

            var superbase = new Supabase.Client(url, key, options);
            if(id == 1)
            {
                var update = await superbase.From<AnimalModel>()
                .Where(x => x.id == model.id)
                .Single();
                update.coordinatesH = model.coordinatesH;
                update.coordinatesW = model.coordinatesW;
                var response = await update.Update<AnimalModel>();
                if(response!=null)
                {
                    return Ok();
                }
                else
                {
                    return NotFound();
                }

            }
            else if(id == 2)
            {
                 var update = await superbase.From<AnimalInfoModel>()
                .Where(x => x.id == model.id)
                .Single();
                update.title= model.title;
                Console.WriteLine(model.title);
                if(model.desc1!="")
                update.desc1= model.desc1;
                else
                update.desc1 = null;

                if(model.desc2!="")
                update.desc2= model.desc2;
                else
                update.desc1 = null;

                if(model.desc3!="")
                update.desc3= model.desc3;
                else
                update.desc1 = null;
                
                update.paragraph = model.paragraph;
                var response = await update.Update<AnimalInfoModel>();
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
            else return NotFound();

        }


    }
}
