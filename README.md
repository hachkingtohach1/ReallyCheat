# ReallyCheat

<img src="https://github.com/hachkingtohach1/Image/blob/main/Logo1.png" alt="ReallyCheat-Premium" height="200" width="200" />

# ReallyCheat ✔
- This is config example version: 0.0.1
- Support software: ```PocketMine-PMMP```          (Nukkit is Next)
- Author: ```hachkingtohach1(DragoVN)```
```---
# -----------------------------------------------------------------------------------------
#
# Modules can detect
# 
# AutoClick                      - (Transfer)                   100% detect
# RapidHit                       - (Transfer)                   100% detect
# KillAura                       - (Transfer)                   100% detect
# HitBox                         - (Transfer)                   100% detect
# Reach                          - (Transfer)                   90% detect
# Fly                            - (Transfer)                   100% detect
# NoClip                         - (Transfer)                   100% detect
# NoWeb                          - (Transfer)                   100% detect
# JetPack                        - (Transfer)                   100% detect
# AirJump                        - (Transfer)                   100% detect
# HighJump                       - (Transfer)                   100% detect
# Glide                          - (Transfer)                   100% detect
# AntiVoid                       - (Transfer)                   95% detect
# Speed                          - (Transfer)                   99% detect
# Jesus                          - (Transfer)                   99% detect
# AutoMidTP                      - (Transfer)                   100% detect
# ClickTp                        - (Transfer)                   100% detect
# Step                           - (Transfer)                   100% detect
# AimAssist                      - (Transfer)                   90% detect
# AutoArmor                      - (Transfer)                   90% detect
# FastLadder                     - (Transfer)                   80% detect
# Spider                         - (Transfer)                   90% detect
# TriggerBot                     - (Transfer)                   100% detect
# NoPacket                       - (Transfer)                   100% detect
# Velocity/NoKB                  - (Transfer)                   100% detect
# ChestAura/ChestStealer         - (Transfer)                   100% detect
# InventoryCleaner               - (Transfer)                   100% detect
# InventoryMove                  - (Transfer)                   100% detect
# Timer                          - (FLAG/Transfer)              100% detect
# Phase                          - (FLAG)                       100% detect
# VClip                          - (FLAG)                       100% detect
# InstaBreak                     - (FLAG/Transfer)              100% detect
# Spam                           - (CAPTCHA)                    100% detect
# Tower                          - (Ban Immediately)            100% detect
# Scaffold                       - (Transfer/Ban Immediately)   100% detect
# Nuker, FastBreak, FillBlock    - (Ban Immediately)            100% detect
#      >> This is module using a special method that requires an API <<
#    <If the server you are using a method intended for the digging of special players.>
#       Example: 
#              <<
#                 // $player must instance of Player from PMMP //
#                 $api = API::getInstance()->getRCPlayer($player);
#                 $api->setAttackSpecial(< true or false >);
#                 $api->setBlocksBrokeASec(< it must is number >);
#                                                                   >>
#
# BadPackets Total: 17
#
# -----------------------------------------------------------------------------------------
#
reallycheat:
    prefix: "§cRCheat §8>"
    version: "0.0.1" #This config version
    antibot:
       message: "§cSorry! You are humman?"
    network:
        #This is to limit the player's access to the same address to the server.
        limit: 3
        message: "§cSorry the server can't allow access to cross the line."
    ping: 
        #This is a customization that helps ReallyCheat test the best cheating players.
        #This is the buyer's discretion if you have custom errors with your server we will not be responsible.
        normal: 20
        lagging: 200   
    proxy:
        ##This is a feature that is still in the development stage. DON'T ENABLE
        enable: false #Enabling this feature will help every server you're running on your computer be protected by ReallyCheat.
        ip: 127.0.0.1
        port: 19132
    process: 
        auto: true #Enabling this mode will allow ReallyCheat to automatically handle the behavior and issue penalties to the player.
    alerts: 
        message: "{prefix} §f{player} §7failed §f{module} §7(§c{subtype}§7) §7VL §2{violation}"
        enable: true
        admin: false #This will cause the in-game cheat detector to send it to the person with the permissions below 
        permission: "reallycheat.notify"
    ban:
        commands:
            - "ban {player} You are hacking!"
        message: "{prefix} §f{player} §chas been removed from server for hacking or abuse."
        enable: true
        randomize: false
        recentlogs:
            message: "{time} > {player} failed {module} ({subtype}) VL {violation} | penalty: BAN"
    transfer:             
        ip: "play.example.net:19132" #If "usecommand" enabled, it will not work  
        usecommand:
            enable: false
            commands:
                - "transfer {player} play.example.net"
        enable: true
        message: "{prefix} §f{player} §chas been kicked from server for hacking or abuse."
        randomize: false
        recentlogs:
            message: "{time} > {player} failed {module} ({subtype}) VL {violation} | penalty: TRANSFER"       
    captcha:
        enable: true
        text: "{prefix} §cType §b{code} §cto get rid of mute!"
        message: true
        tip: false
        title: false
        randomize: false #If it enable <message, tip, title> must disable
        code:
            length: 5
    permissions:
        bypass:
            enable: false
            permission: "reallycheat.bypass"
    discord:
        enable: false
        webhook: ""
        player:
            joined:
                enable: false 
                text: "{player} has joined the server!"
            left:
                enable: false
                text: "{player} has left the server!"
            kick:
                enable: false
                text: "{player} has been kicked from server for hacking or abuse! When using: {module}"
            ban:
                enable: false
                text: "{player} has been removed from server for hacking or abuse! When using: {module}"
        server:
            lagging:
                enable: false 
                text: "Server is lagging! ReallyCheat can't check hacker! Tick: {tick}"
    chat:
        spam:
            text: "{prefix} §cSo sorry! Each chat only has a fixed time of 2 seconds apart."
            delay: 2
        command:
            text: "{prefix} §cSo sorry! Each command only has a fixed time of 2 seconds apart."
            delay: 2
            commands:
                - "kill"
        repeat:
            text: "{prefix} §cSo sorry! Don't go back to your chat last time!"
    check:
        autoclick: 
            enable: true
            maxvl: 1
        killaura: 
            enable: true
            maxvl: 1
        aimassist: 
            enable: true
            maxvl: 1
        wrongpitch:
            enable: true
            maxvl: 1
        crasher:
            enable: true
            maxvl: 1
        scaffold:
            enable: true
            maxvl: 1
        inventorycleaner:
            enable: true
            maxvl: 1
        antivoid:
            enable: true
            maxvl: 1
        speed:
            enable: true
            maxvl: 1
        cheststealer:
            enable: true
            maxvl: 1
        instabreak:
            enable: true
            maxvl: 1
        wrongmining:
            enable: true
            maxvl: 1
        blockreach:
            enable: true
            maxvl: 1
        fillblock:
            enable: true
            maxvl: 1
        spam:
            enable: true
            maxvl: 1
        reach:
            enable: true
            maxvl: 1
        fly: 
            enable: true
            maxvl: 1
        autoarmor:
            enable: true
            maxvl: 1
        chestaura:
            enable: true
            maxvl: 1
        inventorymove:
            enable: true
            maxvl: 1
        airmovement:
            enable: true
            maxvl: 1
        phase:
            enable: true
            maxvl: 1
        step:
            enable: true
            maxvl: 1
        wrongnetwork:
            enable: true
            maxvl: 1
        custompayload:
            enable: true
            maxvl: 1
        velocity:
            enable: true
            maxvl: 1
        timer:
            enable: true
            maxvl: 3
...```
