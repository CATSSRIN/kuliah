program VectoredInt
    var v integer
       
    sub InputInt intr 1
        read(nowait, v)
    end sub
    
    v = 0
    writeln("Program Starting")
    while true
        for i = 1 to 100
            if v > 0 then
                break *
            end if
            write(".")
        next
    wend
    writeln("Program Ending")
end

/*open cpu compiler
/*paste This
/*click on compile on the left bottom
/*and compile
/*go back to cpu simulator
/*click on load compiled code in memory 
/*click on input output
/*set the speed to fast
/*press run
/*see the input output window and put some random number or alphabet on the small box
/*wait till the program ends
