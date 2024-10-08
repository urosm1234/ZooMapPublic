using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;

namespace ZooMapProject.Controllers
{

    [Route("api/[controller]")]
    [ApiController]
    public class AnimalControler : Controller
    {
        // GET: AnimalControler
        [HttpGet("{id?}")]
        public IActionResult Get(int id)
        {
            var animals = new List<string> { "Lion", "Tiger", "Elephant", "Giraffe" };
            if(id<=3)
            {
                return Ok(animals[id]);
            }
            else
            {
                return NotFound();
            }

        }


    }
}
