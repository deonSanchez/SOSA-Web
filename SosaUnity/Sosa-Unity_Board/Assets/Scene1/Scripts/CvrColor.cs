using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CvrColor : MonoBehaviour {

	public float red;
	public float green;
	public float blue;
	Renderer rend;
	void Start()
	{
		rend = gameObject.GetComponent<Renderer> ();
	}
	void Update () 
	{
		rend.material.color = new Color (getCoverRed(), getCoverGreen(), getCoverBlue());
	}
	public void setCoverRed(float x)
	{
		red = x;
	}
	public float getCoverRed()
	{
		return this.red;
	}
	public void setCoverGreen(float x)
	{
		green = x;
	}
	public float getCoverGreen()
	{
		return this.green;
	}
	public void setCoverBlue(float x)
	{
		blue = x;
	}
	public float getCoverBlue()
	{
		return this.blue;
	}
}
