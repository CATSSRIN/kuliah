if (stack full)
    success = false

else
allocate (newptr)
newptr->data = data
newptr->next = stack.top
stack.top = newptr
stack.count = stack.count + 1
success = true

end if 
return success