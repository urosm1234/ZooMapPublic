
using Supabase.Postgrest.Attributes;
using Supabase.Postgrest.Models;
namespace ZooMapProject.Models{
[Table("animal_info")]
    public class AnimalInfoModel : BaseModel
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
        [Column("paragraph")]
        public string paragraph {get; set;} = "";
        [Reference(typeof(AnimalModel))]
        public List<AnimalModel> AnimalModel { get; set; } = new();
    }
    }