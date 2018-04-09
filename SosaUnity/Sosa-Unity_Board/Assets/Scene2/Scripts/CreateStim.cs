using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class CreateStim : MonoBehaviour {
	GameObject Stimulus;
	public GameObject Peg;
	public int count = 0;
	void Start()
	{
	}
	void Update () 
	{
		if(Input.GetKeyDown (KeyCode.P))
		{
			Stimulus = createPeg ();
		}
	}


	public GameObject createPeg()
	{
		if (count == 0) 
		{
			count++;
			return Instantiate (Peg, new Vector3 (Camera.main.transform.position.x, Camera.main.transform.position.y - 6f, Camera.main.transform.position.z), new Quaternion (0, 0, 0, 0), Camera.main.transform);
		}
		return null;
	}
}
