using Supabase.Postgrest.Attributes;
using Supabase.Postgrest.Models;
namespace ZooMapProject.Models;

    [Table("icons")]
    public class AnimalModel : BaseModel
    {
        [PrimaryKey("id",false)]
        public int id { get; set; }  // Animal ID
        [Column("name")]
        public string name { get; set; }  // Name of the animal
        [Column("coordinateh")]
        public int coordinatesH { get; set; }  // Image URL for the animal (optional)
        [Column("coordinatew")]
        public int coordinatesW { get; set; }
        [Column("animal_id")]
        public int animal_id{get;set;}
    }

    [Table("animal_info")]
    public class AnimalInfo : BaseModel
    {
        [PrimaryKey("id",false)]
        public int id { get; set; } 
        [Column("title")]
        public string title { get; set; }

        [Column("desc1")]
        public string desc1 { get; set; } = "";
        [Column("desc2")]
        public string desc2 { get; set; } = ""; 
        [Column("desc3")]
        public string desc3 { get; set; } = "";

        /*[Reference(typeof(AnimalModel))]
        public AnimalModel AnimalModel { get; set; } = new();*/
    }
    