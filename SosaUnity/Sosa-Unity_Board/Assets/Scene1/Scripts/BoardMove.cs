using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class BoardMove : MonoBehaviour 
{
	GameObject Board;
	float speed = 4;
	//I need these from HTML buttons
	bool tiltLock = false;
	bool rotateLock = false;
	bool zoomLock = false;



	void Start()
	{
		Board = GameObject.Find ("Board");
	}
	void Update()
	{
		if (Input.GetMouseButton (0)) 
		{
			float x = Input.GetAxis ("Mouse X") * speed * Mathf.Deg2Rad;
			float y = Input.GetAxis ("Mouse Y") * speed * Mathf.Deg2Rad;
			if (!rotateLock)
			{
				Board.transform.RotateAround (Vector3.up, x);
			}
			if (!tiltLock)
			{
				Board.transform.RotateAround (Vector3.right, y);
			}
		}
		if (Input.GetMouseButton (1)) 
		{
			Board.transform.rotation = Quaternion.identity;
		}
	}
	public void isTiltLocked()
	{
		tiltLock= !tiltLock;
	}
	public void isRotateLocked()
	{
		rotateLock= !rotateLock;
	}
	public void isZoomLocked()
	{
		zoomLock = !zoomLock;
	}
}
