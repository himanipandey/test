import sys


#st = raw_input("Enter input ");


#fh = open("tiger.txt","wb")
#print fh.name
#print fh.closed
#print fh.mode
#fh.write("Hello Wrold!!")
#fh.close()

fp = open("data.text","r+")
st = fp.read();
print st
fp.close();
