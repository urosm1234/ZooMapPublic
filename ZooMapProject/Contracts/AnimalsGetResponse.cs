namespace ZooMapProject.Contracts;


public class AnimalsGetResponse
{
    public int array_id{get; set;}
    public int database_id {get; set;}
    public string name {get; set;}
    public string title {get; set;} = "";
    public float coordinatesH { get; set; }  
    public float coordinatesW { get; set; }
    public string desc1 {get; set;} = "";
    public string desc2 {get; set;} = "";
    public string desc3 {get; set;} = "";
    public string paragraph {get; set;} = "";

    
};

