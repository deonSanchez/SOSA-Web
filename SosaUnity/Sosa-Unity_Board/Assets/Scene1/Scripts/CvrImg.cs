using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CvrImg : MonoBehaviour {

	GameObject CoverImage;
	void Start ()
	{
		CoverImage = GameObject.Find ("CoverImage");
	}


	void Update ()
	{	
		
		if (Input.GetKeyDown (KeyCode.C) && Input.GetKey(KeyCode.LeftShift) || Input.GetButtonDown("Add Image"))
		{
			CoverImage.SetActive(!CoverImage.activeSelf); //Toggle
		}
		//User has to select option of image through HTML and it comes returned to me here
		//Material image = getMaterial();

		//CoverImage.GetComponent<Renderer>.material = image;
	}
	/*Material getMaterial()
	{
		//Application.ExternalCall
		return Material; 
	}*/
}
