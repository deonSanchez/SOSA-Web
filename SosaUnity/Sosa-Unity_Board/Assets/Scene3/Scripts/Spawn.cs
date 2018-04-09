using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Spawn : MonoBehaviour {

	public GameObject Peg;
	int count = 0;
	void Start () 
	{
		SpawnStim ();
	}
	

	void Update () 
	{

	}

	public void SpawnStim(GameObject[] StimSet)
	{
		foreach (GameObject x in StimSet) 
		{
			GameObject Stim = Instantiate (Peg, GameObject.Find ("Spawn"+count+"").transform.position, Quaternion.identity, GameObject.Find ("Board").transform);
			Stim.name = "Stim" + count;
			count++;
		}
	}

	public void SpawnStim()
	{
		Instantiate (Peg, GameObject.Find ("Spawn1").transform.position, Quaternion.identity, GameObject.Find ("Board").transform);
	}


}
