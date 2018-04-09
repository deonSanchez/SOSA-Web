using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.IO;
public class SetToStim : MonoBehaviour 
{

	CreateStim CS;
	PegColor Colors;
	void Start()
	{
		CS = GameObject.Find("SceneController2").GetComponent<CreateStim>();
		Colors = gameObject.GetComponent<PegColor> ();
	}

	public void setName(string aName)
	{
		gameObject.name = aName;
	}
	public string getName()
	{
		return gameObject.name;
	}

	void Update () 
	{
		if (Input.GetKeyDown (KeyCode.S)) 
		{
			CS.count = 0;
			Save ();
		
		}
	}

	void Save()
	{
		Directory.CreateDirectory (Application.dataPath + "/Scene3/Data/" + getName().ToString());
		File.Create (Application.dataPath + "Scene3/Data/" + getName().ToString()+ "/placementData.txt");
		File.Create (Application.dataPath + "Scene3/Data/" + getName().ToString()+  "/ColorData.txt");
		File.Create  (Application.dataPath + "Scene3/Data/" + getName().ToString()+  "/TimeData.txt");
		File.AppendAllText (Application.dataPath + "/Scene3/Data/ColorData.txt", "("+Colors.getPegRed().ToString() +","+ Colors.getPegGreen().ToString()+ ","+ Colors.getPegBlue().ToString() +")"+ "\n");

	}
}
