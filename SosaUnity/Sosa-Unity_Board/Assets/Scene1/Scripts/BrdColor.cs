using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class BrdColor : MonoBehaviour 

{

	public float red;
	public float green;
	public float blue;
	Renderer rend;
	void Awake()
	{
		rend = gameObject.GetComponent<Renderer> ();
	}
	void Update () 
	{
		rend.material.color = new Color (getBoardRed(), getBoardGreen(), getBoardBlue());
	}
	public void setBoardRed(float x)
	{
		red = x;
	}
	private float getBoardRed()
	{
		return this.red;
	}
	public void setBoardGreen(float x)
	{
		green = x;
	}
	private float getBoardGreen()
	{
		return this.green;
	}
	public void setBoardBlue(float x)
	{
		blue = x;
	}
	private float getBoardBlue()
	{
		return this.blue;
	}
}
