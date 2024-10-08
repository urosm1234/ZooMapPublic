using Supabase.Postgrest.Attributes;
using Supabase.Postgrest.Models;

namespace ZooMapProject.Models
{
    [Table("icons")]
    public class AnimalModel : BaseModel
    {
        [Column("id")]
        public int id { get; set; }  // Animal ID
        [Column("name")]
        public string name { get; set; }  // Name of the animal
        [Column("imgUrl")]
        public string ImageUrl { get; set; }  // Description of the animal
        [Column("coordinateH")]
        public int coordinatesH { get; set; }  // Image URL for the animal (optional)
        [Column("coordinateW")]
        public int coordinatesW { get; set; }
    }
}
