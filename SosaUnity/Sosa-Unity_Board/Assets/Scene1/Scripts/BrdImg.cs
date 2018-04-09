using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.IO;
public class BrdImg : MonoBehaviour {

	GameObject BoardImage;
	void Start ()
	{
		BoardImage = GameObject.Find ("BoardImage");
	}
	

	void Update ()
	{	
		if (Input.GetKeyDown (KeyCode.B) && Input.GetKey(KeyCode.LeftShift) || Input.GetButtonDown("Add Image"))
		{
			BoardImage.SetActive(!BoardImage.activeSelf); //Toggle
		}
		//User has to select option of image through HTML options and it comes returned to me here
		//Material image = getMaterial();

		//BoardImage.GetComponent<Renderer>.material = new Material (image);
	}
	/*Material getMaterial()
	{
		Application.ExternalCall
		return Material; 
	}*/
}
