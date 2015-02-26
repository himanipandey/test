#include<iostream>
#include<fstream>
using namespace std;


int main()
{
	ifstream infile;
	string ss;
	infile.open("save.txt");
	while(getline(infile,ss)){
		bool falg = false;
		string latlon = "";
		for(int i = 0 ; i < ss.size(); i++) {
			if(ss[i] == '[') {
			   falg = true;
			   i++;  
			}
			if(ss[i] == ']') {
			   falg = false;
			}
			if(falg == true) {
				latlon = latlon + ss[i];
			}
		}
		cout << latlon << endl;	
	}	
	//for(int i = 0 ; i < ss.size(); i++) {
	//	cout << ss[i];
		/*if(ss[i] == ':') {
			while(ss[i] != '\n'){
				if(ss[i] <= '0' || ss[i] >= '9'){
					cout << ss[i]; 
				}
				i++;
			}
		}*/	
	//}
	return 0;
}
