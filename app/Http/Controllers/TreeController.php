<?php

namespace App\Http\Controllers;

use App\Http\Resources\TreeResource;
use App\Models\Couple;
use App\Models\Person;
use Illuminate\Http\Request;

class TreeController extends Controller
{
    private $nest;
    private $unions;
    private $personss = [];
    private $persons = [];
    private $partners = [];
    private $links = [];
    private $tree = [];

    public function __construct()
    {
        $this->nest = 6;
    }

    public function getChilds(Request $request)
    {
        $person = Person::with(['childs', 'couples'])->get();

        //$this->getGraphData(1, 3);

        $this->getGraphDataUpward($request->start_id ?? 1);
        $test = collect($this->unions)->map(function ($item) {
            $a = collect([$item['partner'], $item['children']])->flatten();
            return $a;
        });

        $ids = [];

        foreach ($test as $key => $t) {
            $ids[] = $t;
        }

        $tree = Person::with(['couples', 'wifes', 'husband'])->whereIn('id', collect($ids)->flatten())->get();

        //dd($tree);

        $data = [
            'unions' => $this->unions,
            'tree' => json_encode(TreeResource::collection($tree)),
            'links' => $this->links,
        ];

        //dd($data);
        //return response()->json($data);
        return view('welcome', $data);
    }

    public function getGraphData($start_id, $nest = 1)
    {
        if ($nest >= $this->nest) {
            $nest++;
            $couples = Couple::where('husband_id', $start_id)->orWhere('wife_id', $start_id)->get();

            foreach ($couples as $couple) {
                $couple_id = $couple->id;
                $couple_name = $couple;
                $father = Person::find($couple->husband_id);
                $mother = Person::find($couple->wife_id);

                if (isset($father->id)) {
                    $_families = Couple::where('husband_id', $father->id)->orwhere('wife_id', $father->id)->select('id')->get();
                    $_union_ids = [];
                    foreach ($_families as $item) {
                        $_union_ids[] = 'u' . $item->id;
                    }
                    $father->setAttribute('own_unions', $_union_ids);
                    $this->persons[$father->id] = $father;
                }

                if (isset($mother->id)) {
                    $_families = Couple::where('husband_id', $mother->id)->orwhere('wife_id', $mother->id)->select('id')->get();
                    $_union_ids = [];
                    foreach ($_families as $item) {
                        $_union_ids[] = 'u' . $item->id;
                    }
                    $mother->setAttribute('own_unions', $_union_ids);
                    $this->persons[$mother->id] = $mother;
                }
            }


            $children = Person::where('parent_id', $couple_id)->get();
            $children_ids = [];
            foreach ($children as $child) {
                $child_id = $child->id;
                // add child to person
                // parent_union
                $child_data = Person::find($child_id);
                $_families = Couple::where('husband_id', $child_id)->orwhere('wife_id', $child_id)->select('id')->get();
                $_union_ids = [];
                foreach ($_families as $item) {
                    $_union_ids[] = $item->id;
                }
                $child_data->setAttribute('own_unions', $_union_ids);
                $this->persons[$child_id] = $child_data;

                // add union-child link
                $this->links[] = ['u' . $couple_id, $child_id];

                // make union child filds
                $children_ids[] = $child_id;
                //$this->getGraphData($child_id, $nest);
            }



            $union = [];
            $union['id'] = $couple_id;
            $union['name'] = $couple_name;
            $union['children'] = $children_ids;
            $union['person'] = $this->persons;
            $union['children'] = $children_ids;
            $this->unions[] = $union;
            $array_combnie = [$children_ids, ...$this->persons];
        }

        return true;
    }

    private function getGraphDataUpward($start_id, $nest = 0)
    {
        $threshold = (int) ($this->nest) * 1;
        $has = (int) ($nest) * 1;
        if ($threshold >= $has) {
            $person = Person::find($start_id);
            // $people = Company::find($start_id)->people();
            // var_dump($people);
            // do not process for null
            if ($person == null) {
                return;
            }

            // do not process again
            if (array_key_exists($start_id, $this->persons)) {
                return;
            }
            // do self
            if (!array_key_exists($start_id, $this->persons)) {
                // this is not added
                $_families = Couple::where('husband_id', $start_id)->orwhere('wife_id', $start_id)->select('id')->get();
                $_union_ids = [];
                foreach ($_families as $item) {
                    $_union_ids[] = 'u' . $item->id;
                    // add current family link
                    // $this->links[] = [$start_id, 'u'.$item->id];
                    array_unshift($this->links, [$start_id, 'u' . $item->id]);
                }
                $person->setAttribute('own_unions', $_union_ids);
                $person->setAttribute('parent_union', 'u' . $person->parent_id);
                // add to persons
                $this->persons[$start_id] = $person;

                // get self's parents data
                $p_family_id = $person->parent_id;
                if (!empty($p_family_id)) {
                    // add parent family link
                    // $this->links[] = ['u'.$p_family_id,  $start_id] ;
                    array_unshift($this->links, ['u' . $p_family_id,  $start_id]);
                    $p_family = Couple::find($p_family_id);
                    if (isset($p_family->husband_id)) {
                        $p_fatherid = $p_family->husband_id;
                        $this->getGraphDataUpward($p_fatherid, $nest + 1);
                    }
                    if (isset($p_family->wife_id)) {
                        $p_motherid = $p_family->wife_id;
                        $this->getGraphDataUpward($p_motherid, $nest + 1);
                    }
                }
            }
            // get partner
            $cu_families = Couple::where('husband_id', $start_id)->orWhere('wife_id', $start_id)->get();
            foreach ($cu_families as $family) {
                $family_id = $family->id;
                $father = Person::find($family->husband_id);
                $mother = Person::find($family->wife_id);
                if (isset($father->id)) {
                    if (!array_key_exists($father->id, $this->persons)) {
                        // this is not added
                        $_families = Couple::where('husband_id', $father->id)->orwhere('wife_id', $father->id)->select('id')->get();
                        $_union_ids = [];
                        foreach ($_families as $item) {
                            $_union_ids[] = 'u' . $item->id;
                        }
                        $father->setAttribute('own_unions', $_union_ids);
                        $father->setAttribute('parent_union', 'u' . $father->parent_id);
                        // add to persons
                        $this->persons[$father->id] = $father;

                        // add current family link
                        // $this->links[] = [$father->id, 'u'.$family_id];
                        array_unshift($this->links, [$father->id, 'u' . $family_id]);
                        // get husband's parents data
                        $p_family_id = $father->parent_id;
                        if (!empty($p_family_id)) {
                            // add parent family link
                            // $this->links[] = ['u'.$p_family_id,  $father->id] ;
                            array_unshift($this->links, ['u' . $p_family_id,  $father->id]);
                            $p_family = Couple::find($p_family_id);
                            if (isset($p_family->husband_id)) {
                                $p_fatherid = $p_family->husband_id;
                                $this->getGraphDataUpward($p_fatherid, $nest + 1);
                            }
                            if (isset($p_family->wife_id)) {
                                $p_motherid = $p_family->wife_id;
                                $this->getGraphDataUpward($p_motherid, $nest + 1);
                            }
                        }
                    }
                }
                if (isset($mother->id)) {
                    if (!array_key_exists($mother->id, $this->persons)) {
                        // this is not added
                        $_families = Couple::where('husband_id', $mother->id)->orwhere('wife_id', $mother->id)->select('id')->get();
                        $_union_ids = [];
                        foreach ($_families as $item) {
                            $_union_ids[] = $item->id;
                        }
                        $mother->setAttribute('own_unions', $_union_ids);
                        $mother->setAttribute('parent_union', 'u' . $mother->parent_id);
                        // add to person
                        $this->persons[$mother->id] = $mother;
                        // add current family link
                        // $this->links[] = [$mother->id, 'u'.$family_id];
                        array_unshift($this->links, [$mother->id, 'u' . $family_id]);
                        // get wifee's parents data
                        $p_family_id = $mother->parent_id;
                        if (!empty($p_family_id)) {
                            // add parent family link
                            // $this->links[] = ['u'.$p_family_id,  $father->id] ;
                            array_unshift($this->links, ['u' . $p_family_id,  $mother->id]);

                            $p_family = Couple::find($p_family_id);
                            if (isset($p_family->husband_id)) {
                                $p_fatherid = $p_family->husband_id;
                                $this->getGraphDataUpward($p_fatherid, $nest + 1);
                            }
                            if (isset($p_family->wife_id)) {
                                $p_motherid = $p_family->wife_id;
                                $this->getGraphDataUpward($p_motherid, $nest + 1);
                            }
                        }
                    }
                }

                // find children
                $children = Person::where('parent_id', $family_id)->get();
                $children_ids = [];
                foreach ($children as $child) {
                    $child_id = $child->id;
                    $children_ids[] = $child_id;
                    $this->getGraphDataUpward($child->id, $nest);
                }

                // compose union item and add to unions
                $union = [];
                $union['id'] = 'u' . $family_id;
                $union['partner'] = [isset($father->id) ? $father->id : null, isset($mother->id) ? $mother->id : null];
                //$this->tree[] = [isset($father->id) ? $father : null, isset($mother->id) ? $mother : null];
                $union['children'] = $children_ids;
                $this->unions['u' . $family_id] = $union;;
            }
            // get brother/sisters
            $brothers = Person::where('parent_id', $person->parent_id)
                ->whereNotNull('parent_id')
                ->where('id', '<>', $start_id)->get();
            // $nest = $nest -1;
            foreach ($brothers as $brother) {
                $this->getGraphDataUpward($brother->id, $nest);
            }
        } else {
            return;
        }
    }
}
