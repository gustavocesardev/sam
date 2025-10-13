import 'package:flutter/material.dart';

class FabConfigVO {
  final Icon icon;
  final String? route;
  final WidgetBuilder? builder;

  const FabConfigVO({required this.icon, this.route, this.builder})
    : assert(
        route != null || builder != null,
        'É necessário fornecer ou uma route ou um builder',
      );
}
