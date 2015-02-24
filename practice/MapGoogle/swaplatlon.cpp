#include<iostream>
#include<fstream>

using namespace std;

int main()
{
	ifstream infile;
	infile.open("latlonunorder.txt");
	string ss;
	while(getline(infile,ss)) {
		string lat = "";
		string lon = "";
		bool flag = false;
		for(int i = 0 ; i < ss.size(); i++) {
			if(ss[i] == ',') {
				flag = true;
				i = i + 2;
			}
			if(flag) {
				lat = lat + ss[i];
			}else{
				lon = lon + ss[i];
			}
		}
		cout <<  "new google.maps.LatLng( " << lat << ","  << lon << ")," << endl;
	}
	return 0;
}
