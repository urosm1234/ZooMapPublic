namespace ZooMapProject.Contracts;


public class AnimalsGetResponse
{
    public int id{get; set;}
    public string name {get; set;}
    public string title {get; set;} = "";
    public int coordinatesH { get; set; }  
    public int coordinatesW { get; set; }
    public string desc1 {get; set;} = "";
    public string desc2 {get; set;} = "";
    public string desc3 {get; set;} = "";
    public string paragraph {get; set;} = "";

    
};

