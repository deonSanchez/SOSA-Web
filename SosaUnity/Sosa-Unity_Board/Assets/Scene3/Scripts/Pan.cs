using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Pan : MonoBehaviour
{
	public float panSpeed;
	public GameObject panCube;
	bool canPanLeft, canPanRight, canPanUp, canPanDown;

	void FixedUpdate ()
	{
		checkInBounds ();

			//Left
		if (Input.GetKey (KeyCode.A) && canPanLeft) //-22
				transform.Translate (panCube.transform.right * Time.deltaTime * -panSpeed);
			//Right
		if (Input.GetKey (KeyCode.D) && canPanRight) //+22
				transform.Translate (panCube.transform.right * Time.deltaTime * panSpeed);
			//Up
		if (Input.GetKey (KeyCode.W) && canPanUp) // 2
				transform.Translate (panCube.transform.up * Time.deltaTime * panSpeed);
			//Down
		if (Input.GetKey (KeyCode.S) && canPanDown) // -9
				transform.Translate (panCube.transform.up * Time.deltaTime * -panSpeed);
		

	}
	void checkInBounds()
	{
		if (gameObject.transform.localPosition.x <= -10)
			canPanLeft = false;
		else
			canPanLeft = true;

		if (gameObject.transform.localPosition.x >= 10)
			canPanRight = false;
		else
			canPanRight = true;

		if (gameObject.transform.localPosition.y >= 2)
			canPanUp = false;
		else
			canPanUp = true;

		if (gameObject.transform.localPosition.y <= -14)
			canPanDown = false;
		else
			canPanDown = true;
	}

}
