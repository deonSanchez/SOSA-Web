using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Rotation : MonoBehaviour {


	public float speed;
	void Update ()
	{

		if (Input.GetKey (KeyCode.RightArrow) && rightRotationAtLimit () == false) 
			transform.Rotate (Vector3.up * -1 * Time.deltaTime * speed);
		
		if (Input.GetKey (KeyCode.LeftArrow) && leftRotationAtLimit () == false) 
			transform.Rotate (Vector3.up * Time.deltaTime * speed);
		
		if (Input.GetKey(KeyCode.Space)) 
			transform.rotation = Quaternion.identity;
	}




//Code to check for rotational limit to the right
	private bool rightRotationAtLimit()
	{
		float angle = transform.rotation.eulerAngles.y;

		angle = (angle > 180) ? angle - 360 : angle;

	if (angle <= -70)
			return true;
		else 
			return false;
	}

//Code to check for rotational limit to the left
	private bool leftRotationAtLimit()
	{
		float angle = transform.rotation.eulerAngles.y;

		angle = (angle > 180) ? angle - 360 : angle;

		if (angle >= 70)
			return true; 
		else
			return false;
	}

		
}
