-- Takes arguments in the following order:
-- arg[1] = IP Address / Hostname
-- arg[2] = password
-- arg[3] = Port (if not default)
if arg[1] == nil then
	print("Error! No address specified")
	os.exit()
end
port = arg[3] or 36330

function read(filter)
	a,err = conn:receive(filter)
	if err == nil then
		return a
	else
		print(err)
		os.exit()
	end
end

socket = require("socket")

conn = socket.connect(arg[1], port)
if string.sub(read("*l"),23,34) ~= "Folding@home" then
	print("Error, are you sure you are connected to a folding client?")
	conn:send("exit\n")
	os.exit()
end

conn:send("configured\n")
if string.sub(read("*l"),1,4) ~= "PyON" then
	if arg[2] == nil then
		print("Error, need a password but none specified!")
		conn:send("exit\n")
		os.exit()
	else	
		conn:send("auth "..arg[2].."\n")
		if string.sub(read("*l"),3,4) ~= "OK" then
			print("Wrong password!")
			conn:send("exit\n")
			os.exit()
		end
	end
else
	read("*l")
	read("*l")
end

conn:send("queue-info\n")
conn:send("exit\n")
print(string.sub(read("*a"),16,-8))
