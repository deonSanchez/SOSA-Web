using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using System.IO;
public class DataHold : MonoBehaviour
{

	string Name;
	bool isHeld = false;
	float timeHeld = 0;
	int amountPickedUp = 0;
	string placements;
	string TimeStamps;
	// Use this for initialization
	void Start () 
	{
		transform.localScale = new Vector3 (0.416f, 1f, 0.416f);
	}
		
	void Update()
	{
		if (Input.GetMouseButtonUp (0) && isHeld) 
		{
//Setting up Time Stamps
			isHeld = false;
			amountPickedUp++;
			TimeStamps  = "[" + amountPickedUp + "]" + "[" + timeHeld + "]";
			timeHeld = 0;
//Getting Placement on board
			placements = "("+ Mathf.Round(((-transform.localPosition.x/5)*12)*100)/100 + "," + Mathf.Round(((-transform.localPosition.z/5)*12)*100)/100 + ")";
			WriteToFile1 (TimeStamps);
			WriteToFile2 (placements);

		}
	}
	void OnMouseDrag()
	{
		isHeld = true;
		timeHeld += Time.deltaTime;
	}

	void WriteToFile1 (string TimeStamp)
	{
		File.AppendAllText (Application.dataPath + "/Scene3/Data/TimeData.txt", TimeStamps + "\n");
	}
	void WriteToFile2 (string placement)
	{
		File.AppendAllText (Application.dataPath + "/Scene3/Data/PlaceData.txt", placements + "\n");
	}
}
