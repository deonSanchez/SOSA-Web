using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
public class PegMove : MonoBehaviour 
{

	private float distance;

	void Start()
	{
		#if !UNITY_EDITOR && UNITY_WEBGL
		WebGLInput.captureAllKeyboardInput = false;
		#endif
	}

	void Update()
	{
		if (Input.GetMouseButtonUp (0))
			Cursor.visible = true;
		distance = Vector3.Distance (transform.position, Camera.main.transform.position);
	}

	void OnMouseDown()
	{
		distance = Vector3.Distance (transform.position, Camera.main.transform.position);
	
	}
	void OnMouseDrag()
	{
		Cursor.visible = false;
		Ray ray = Camera.main.ScreenPointToRay (Input.mousePosition);
		Vector3 rayPoint = ray.GetPoint (distance);
		transform.position = new Vector3 (rayPoint.x,transform.position.y,rayPoint.z);
		Debug.DrawRay(Camera.main.transform.position,transform.position,Color.blue,20);
	}
}
