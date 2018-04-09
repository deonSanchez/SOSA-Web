using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class BckColor : MonoBehaviour {

	public float red;
	public float green;
	public float blue;
	Renderer rendFront;
	Renderer rendBack;
	Renderer rendLeft;
	Renderer rendRight;
	Renderer rendCeiling;
	Renderer rendFloor;
	void Start()
	{
		#if !UNITY_EDITOR && UNITY_WEBGL
		WebGLInput.captureAllKeyboardInput = false;
		#endif
		rendFront = GameObject.Find("FrontWall").GetComponent<Renderer> ();
		rendBack = GameObject.Find("BackWall").GetComponent<Renderer> ();
		rendLeft = GameObject.Find("LeftWall").GetComponent<Renderer> ();
		rendRight = GameObject.Find("RightWall").GetComponent<Renderer> ();
		rendCeiling = GameObject.Find("Ceiling").GetComponent<Renderer> ();
		rendFloor = GameObject.Find("Floor").GetComponent<Renderer> ();
	}
	void Update () 
	{
		rendFront.material.color = new Color (getBackRed(), getBackGreen(), getBackBlue());
		rendBack.material.color = new Color (getBackRed(), getBackGreen(), getBackBlue());
		rendLeft.material.color = new Color (getBackRed(), getBackGreen(), getBackBlue());
		rendRight.material.color = new Color (getBackRed(), getBackGreen(), getBackBlue());
		rendCeiling.material.color = new Color (getBackRed(), getBackGreen(), getBackBlue());
		rendFloor.material.color = new Color (getBackRed(), getBackGreen(), getBackBlue());
	}
	public void setBackRed(float x)
	{
		red = x;
	}
	public float getBackRed()
	{
		return this.red;
	}
	public void setBackGreen(float x)
	{
		green = x;
	}
	public float getBackGreen()
	{
		return this.green;
	}
	public void setBackBlue(float x)
	{
		blue = x;
	}
	public float getBackBlue()
	{
		return this.blue;
	}
}
