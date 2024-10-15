using Supabase.Postgrest.Attributes;
using Supabase.Postgrest.Models;
namespace ZooMapProject.Models
{
    [Table("icons")]
    public class AnimalModel : BaseModel
    {
        [PrimaryKey("id",false)]
        public int id { get; set; }  // Animal ID
        [Column("name")]
        public string name { get; set; }  // Name of the animal
        [Column("coordinateh")]
        public float coordinatesH { get; set; }  // Image URL for the animal (optional)
        [Column("coordinatew")]
        public float coordinatesW { get; set; }
        [Column("animal_id")]
        public int animal_id{get;set;}
    }
}   
    
    
    