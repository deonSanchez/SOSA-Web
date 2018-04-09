using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CvrToggle : MonoBehaviour 
{

	GameObject Cover;
	void Start()
	{
		 Cover = GameObject.Find ("Cover");
		 Cover.SetActive (false);
	}
	void Update()
	{
		if (Input.GetKeyDown (KeyCode.T)) 
		{
			toggleCover();
		}
	}
	public void toggleCover()
	{
		Cover.SetActive (!Cover.activeSelf);
	}
}
