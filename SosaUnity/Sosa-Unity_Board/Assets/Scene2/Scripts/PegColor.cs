using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class PegColor : MonoBehaviour {

	public float red;
	public float green;
	public float blue;
	Renderer rend;
	void Awake()
	{
		rend = gameObject.GetComponent<Renderer> ();
		red = .5f;
		green = .5f;
		blue = .5f;
	}
	void Update () 
	{
		rend.material.color = new Color (getPegRed(), getPegGreen(), getPegBlue());
	}
	public void setPegRed(float x)
	{
		red = x;
	}
	public float getPegRed()
	{
		return this.red;
	}
	public void setPegGreen(float x)
	{
		green = x;
	}
	public float getPegGreen()
	{
		return this.green;
	}
	public void setPegBlue(float x)
	{
		blue = x;
	}
	public float getPegBlue()
	{
		return this.blue;
	}
}
