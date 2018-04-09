using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class PegRot : MonoBehaviour {

	public float speed;


	void Start()
	{
		
	}
	void Update()
	{
		if (Input.GetMouseButton (0)) 
		{
			float x = Input.GetAxis ("Mouse X") * speed * Mathf.Deg2Rad;
			float y = Input.GetAxis ("Mouse Y") * speed * Mathf.Deg2Rad;
			gameObject.transform.RotateAround (-Vector3.forward, x);
			gameObject.transform.RotateAround (Vector3.right, y);
		}
		if (Input.GetMouseButton (1))
			gameObject.transform.rotation = new Quaternion (0, 0, 0, 0);
	}
}
