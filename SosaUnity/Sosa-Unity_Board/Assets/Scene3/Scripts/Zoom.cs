using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Zoom : MonoBehaviour {
	
	public float speed;
	Vector3 originalPosition;
	Quaternion originalRotation;
	public GameObject zoomCube;
	bool canZoomIn;
	bool canZoomOut;

	void Awake()
	{
		originalPosition = transform.position;
		originalRotation = transform.rotation;
	
	}
	void Update () 
	{
		checkInBounds ();
	
		if (Input.GetKey (KeyCode.UpArrow) && canZoomIn) 
			transform.Translate (zoomCube.transform.forward * Time.deltaTime * speed);

		if (Input.GetKey (KeyCode.DownArrow) && canZoomOut)
			transform.Translate (zoomCube.transform.forward * Time.deltaTime * -speed);
		
		if (Input.GetKey (KeyCode.Space)) 
		{
			transform.position = originalPosition;
			transform.rotation = originalRotation;
		}
	}

	void checkInBounds()
	{
		if (gameObject.transform.localPosition.z >= -15.5f)
			canZoomIn = false;
		else
			canZoomIn = true;
		
		if (gameObject.transform.localPosition.z <= -29.7f)
			canZoomOut = false;
		else
			canZoomOut = true;
		
	}
}
