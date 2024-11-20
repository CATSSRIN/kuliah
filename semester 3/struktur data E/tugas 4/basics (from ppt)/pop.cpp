if (stack empty)
    success = false

else
dltptr = stack.top
dataout = tsack.top->data
stack.top = stack.top->next
stack.count = stack.count - 1
recycle (dltptr)
success = true

end if 
return success