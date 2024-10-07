using Microsoft.AspNetCore.Mvc;
using System.Collections.Generic;

namespace ZooMapProject.Controllers
{

    [Route("api/animal")]
    [ApiController]
    public class AnimalControler : Controller
    {
        // GET: AnimalControler
        [HttpGet("{id?}")]
        public ActionResult Index(int id)
        {
            var animals = new List<string> { "Lion", "Tiger", "Elephant", "Giraffe" };
            return Ok(animals[id]);
        }

    }
}
