.data
message:    .asciz "Enter an emoticon: :D "
emoticon:   .space 2

    .text
    .global _start

_start:
    // Print message
    mov r0, 1
    ldr r1, =message
    ldr r2, =15
    mov r7, 4
    swi 0

    // Read emoticon
    mov r0, 0
    ldr r1, =emoticon
    ldr r2, =2
    mov r7, 3
    swi 0

    // Print emoticon
    mov r0, 1
    ldr r1, =emoticon
    ldr r2, =2
    mov r7, 4
    swi 0

    // Donut loop
loop:
    b loop

    // Exit program
exit:
    mov r0, 0
    mov r7, 1
    swi 0
